/!\ Work in progress /!\

This project use a soft to gather market data on Albion, and proceed those data in useful tools and metrics.

In order to provide fresh data to this project, you have to use the Albion Data Client. Note from the authors on ADC :
> A quick note on the legality of this application and if it violates the Terms and Conditions for Albion Online. Here is the response from SBI when asked if we are allowed to do monitor network packets relating to Albion Online:
Our position is quite simple. As long as you just look and analyze we are ok with it. The moment you modify or manipulate something or somehow interfere with our services we will react (e.g. perma-ban, take legal action, whatever). ~ MadDave - Technical Lead for Albion Online
Source: https://forum.albiononline.com/index.php/Thread/51604-Is-it-allowed-to-scan-your-internet-trafic-and-pick-up-logs/?postID=512670#post512670
This client monitors local network traffic, identifies UDP packets that contain relevant data for Albion Online, and ships the information off to a central NATS server that anyone can subscribe to.

To use Albion Data Client, you have to :
- Download the latest version here : https://github.com/broderickhyman/albiondata-client/releases
- Install it
- When playing Albion, launch it (before or after albion, it doesn't matters)
- To be sure that your data are useful, right-click on the icon in the notification bar => Show console. All orders got from the auction house will be sent to the database and analyzed with our tools
- Profit

More on ADC : https://github.com/broderickhyman/albiondata-client