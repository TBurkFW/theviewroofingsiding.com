# FaithWorks Boilerplate

The first step it to create a new repo from the boilerplate template. This allows us to check in code and create configuration for the new site.

## Create a repository from the Boilerplate template

- To create a new repository from the template, go to the template code page at https://github.com/TBurkFW/FaithWorksBoilerplate/tree/main and click `Use This Template`. You can also create a new repo and select the template from the dropdown box.
- Name the repository the name of the domain name to keep things consistent

---

# Site Configuration

## 1. Create Lightsail Instance

- Create a new Lightsail instance at https://lightsail.aws.amazon.com/ls/webapp/home/instances (1 GB RAM, 2 vCPUs, 40 GB SSD - WordPress)
- Use the domain name for the instance to keep everything standardized
- Once the instance is up, go to the networking tab and assign it a static IP. This will allow it to keep the same IP even if you reboot. This is necessary for DNS to continue working.

Set Up DNS Build Record
Login to the DNS host
In case of subdomain (ex: subdomain.myfaithimages.com)
Add new DNS Record Type: A  Name: subdomain Value: Static IP TTL: ½ hr
In case of regular or primary domain (ex: www.upci.org)
Edit Main A Record Type: A  Name: @ Value: Static IP TTL: ½ hr

## 2. Create AWS Site User

We need to create an AWS user for S3 uploads and sending SES emails.

- Open the AWS console and go to the IAM screen to create a new user. https://us-east-1.console.aws.amazon.com/iamv2/home?region=us-east-2#/users/create
- Enter the domain name of the site for the user to keep things standardized
- Do not check `Provide user access to the AWS Management Console`
- On the next screen add the user to the group `WordPressSites`
- Once the user is created, click on the user and go to the Security Credentials Tab
- Go to Access Key and click "Create Access Key"
- Click the `Application running on an AWS compute service` and then check the Confirmation box
- Skip the tag screen and click Create
- You will see the `Retrieve access keys`. Click the Download CSV button. Keep these values somewhere safe. We will use them in the next steps as well.

## 3. Add Github Secrets

Secrets are entered and encrypted on Github's backend. Do not worry if you can't see the secrets on the UI. You will need to add each of these to a new site. To use actions properly, we need to store a few secrets configurations about Lightsail in the repository Settings -> Secrets and Variables -> Actions. Example: https://github.com/TBurkFW/bbrave.life/settings/secrets/actions

If you wish to change a variable or secret value to reflect on the site, you must run the Deploy To Lightsail action/workflow after the change to see it live.

- LIGHTSAIL_IP - The instance static IP
- LIGHTSAIL_KEY - Go to the Lightsail instance on AWS Console and click download default key on your Lightsail instance. Open the .pem file in TextEdit and copy/paste the ENTIRE contents of the file to this secret
- LIGHTSAIL_USER - `bitnami` (Will always be the same value for Lightsail)
- AWS_KEY - The AWS key generated in the previous step
- AWS_SECRET - The AWS secret generated in the previous step

To get the Lightsail database password, connect to your instance by clicking the orange button. This will open a SSH terminal. Paste in the following command to get these values. 
`while read -r line; do [[ "$line" =~ ^.*(DB_PASSWORD).*$ ]] && echo $line; done < stack/wordpress/wp-config.php` Copy and past just the password, no quotation marks.

- DATABASE_PASSWORD

## 4. Add Github Variables

Variables are meant to be public and you can see the values. You will need to add each of these to a new site. To use actions properly, we need to store a few variables about Lightsail in the repository Settings -> Secrets and Variables -> Actions. Example: https://github.com/TBurkFW/bbrave.life/settings/secrets/actions

- ADMIN_EMAIL - Use a verified email in SES. https://us-east-2.console.aws.amazon.com/ses/home?region=us-east-2#/verified-identities
- DOMAIN - `www.yourdomain.com` (no https://)

## 5. Add WordPress Salts

Add the following values from this generator (no quotation marks): https://api.wordpress.org/secret-key/1.1/salt/

Copy the values generated and paste them into config/wp-config.php where you see the following:
```
define('AUTH_KEY',         '%AUTH_KEY%');
define('SECURE_AUTH_KEY',  '%SECURE_AUTH_KEY%');
define('LOGGED_IN_KEY',    '%LOGGED_IN_KEY%');
define('NONCE_KEY',        '%NONCE_KEY%');
define('AUTH_SALT',        '%AUTH_SALT%');
define('SECURE_AUTH_SALT', '%SECURE_AUTH_SALT%');
define('LOGGED_IN_SALT',   '%LOGGED_IN_SALT%');
define('NONCE_SALT',       '%NONCE_SALT%');
```

## 6. Configure Plugins/Themes

### Free Plugins/Themes

You can install publicly available free plugins and themes using Composer. Composer is an easy way to pull in plugins and themes without having to store the code in github. It will install plugins/themes to their respective folders when the site is deployed.

- Add plugins or themes to the Composer file is in the root at `composer.json`
- Search for official WordPress packages at https://wpackagist.org (Note: If a package is non-WordPress, use https://packagist.org instead)
- Upon finding the plugin or theme from wpackagist.org, copy the package and version number to the `require` section of `composer.json`
- Make sure you only have numbers and dots in the version number. Do not use `^~><` as these will upgrade plugins without your knowledge on build. By adding a specific version, we are 'pegging' that version to our site. This keeps things stable when deploying.
- After deploying, enable the plugins on the WordPress admin screen

### Premium Plugins

Since we cannot use Composer to include premium plugins into the build, we must manually add them to the `premium-plugins` folder.

- Download the zip file of the plugin from the premium website
- Add the folder via github to the `premium-plugins` folder. Make sure to remove any previous versions of the plugin from the folder.
- Premium plugins will deploy with the main deploy action now.
- Enable the plugin

## 7. Update Bots Directive

Update the `public/robots.txt` file with the proper sitemap URL. Determine the sitemap URL from whatever sitemap generator your are using. This is an important step for SEO for the site.

## 8. Configure SES

Any email address sending mail from the site has to be verified in SES. You can do that here: https://us-east-2.console.aws.amazon.com/ses/home?region=us-east-2#/verified-identities 

When adding a verified identity, select Email (Not Domain) and enter the email address. A confirmation email will be sent to their address. Have them click the link to verify and SES will allow email from that address to be sent. Otherwise it will generate errors on the site.


# Site Deployment and Maintenance

## Github Actions
We use Github actions as a CI/CD pipeline to deploy our sites to Lightsail without having to use FTP. Actions use workflows to do various jobs on the site. There are several pre-configured workflows that will assist in using lightsail without friction. Example: https://github.com/TBurkFW/bbrave.life/actions Actions can be triggered by clicking the Run Workflow button on a specific action.

- Deploy to Lightsail - This runs a build and collates all the dependencies from composer.json, adds the secrets and variables, and deploys the whole site to Lightsail.
- Import Database - This is a convenient way to import a database file by adding a SQL file to the faithworksbackups/database/YOURDOMAIN/ folder and specify the file for the action.
- Backup Site - This runs once per week on Sunday at midnight
- Deploy Premium Plugins - This deploys any zip file added to the `premium-plugins` directory to the site. Since we cannot use Composer for non-public plugins, this is the method to add/update those on each site.

# Moving From Development To Production

## 1. Update Github Variable
- Update the DOMAIN variable to the new domain - `www.yourdomain.com` (no https://)

## 2. Update S3 Folders
- Follow step `8. Configure S3 Folders` completely again for the new domain.

## 2. Update Database References

- Enable the Better Search and Replace plugin
- Search for the previous references to `https://oldexactdomain.com` and in the replace field use `https://www.newexactdomain.com`
- Select the database tables you want to search.
- Make sure the Dry Run option is checked and run the search
- You are able to see if there are any references in the database that will be changed.
- After reviewing the dry run results, run the search and replace, and then disable the plugin.

## 3. Add SSL Certificate

It is not recommended to add a SSL certificate to a site until it is production ready. There are several nuances to recreating a SSL for a different domain on a Lightsail instance that need to be worked through first. Simply accept that the site is insecure during development. If you choose to add a SSL certificate to the site before it's in production, email Brian to assist in removing it before attempting to add another one.

### Set DNS
To add a SSL certificate, two conditions must be met by the DNS provider:
- The main `A` record should be pointed to the Lightsail IP address
- The `www` `CNAME` record should be pointed to the Lightsail IP address

Note: Lightsail DNS might cache a previous version of the DNS for an undetermined amount of time. It's recommended that the DNS TTLS be set to 5 minutes a week before the values are pointed to Lightsail. 

### Install Certificate

- Open the terminal/console using the Lightsail dashboard for the current site
- Run this command: `cd stack; sudo ./bncert-tool`
- Enter the domains with www first. BOTH versions of the domain should be entered. This is very important. Example: www.mysite.com, mysite.com
- Select non-www to www redirection.
- Do not select www to non-www redirection! This will break the site.
- Add the admin email for this. This should match what you added to the Github variables. This is VERY important for the renewal as the programmatic line in the cron job is pinned to this email address.
- Agree to the terms and follow the rest of the prompts.
- It will tell you if there is an error or success.
- Rejoice if success. Email Brian if error.
