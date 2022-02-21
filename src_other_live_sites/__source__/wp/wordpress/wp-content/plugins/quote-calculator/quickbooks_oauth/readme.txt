This code is designed to be the simplest example to get started with OAuth 
and pull data from the QuickBooks Online (QBO) API. This is an example only as
there are potential security issues with this code example. We encourage you to 
use this example to get started, then improve the code for your environment.

If you have issues, questions or feedback you can email: pearce@intuit.com

If you are brand new:
Walk through making your first calls using the API Explorer:
https://developer.intuit.com/docs/0025_quickbooksapi/0010_getting_started/0007_firstrequest

Prerequisites: 
1) A registered QBO API App
2) A QBO Account with customer data
- if you don't have either of these then follow the walkthrough in the above link.

How to run this code:
1) In the config.php file
- Copy and paste the consumer key and consumer secret to the respective variables.
  - You get the key and secret from your app page registered at developer.intuit.com
2) Run the code
3) Click login with Intuit
4) Login with your QuickBooks account
5) Authorize app to have access to your QBO data

Results: Main page should refresh and pull in at most 10 customers from your QBO data.
 