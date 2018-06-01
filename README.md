/!\ Work in progress /!\

This project use a soft to gather market data on Albion, and proceed those data in useful tools and metrics.

In order to provide fresh data to this project, you have to use the Albion Data Client. Note from the autors on ADC :
A quick note on the legality of this application and if it violates the Terms and Conditions for Albion Online. Here is the response from SBI when asked if we are allowed to do monitor network packets relating to Albion Online:
Our position is quite simple. As long as you just look and analyze we are ok with it. The moment you modify or manipulate something or somehow interfere with our services we will react (e.g. perma-ban, take legal action, whatever).
~ MadDave - Technical Lead for Albion Online
Source: https://forum.albiononline.com/index.php/Thread/51604-Is-it-allowed-to-scan-your-internet-trafic-and-pick-up-logs/?postID=512670#post512670
This client monitors local network traffic, identifies UDP packets that contain relevant data for Albion Online, and ships the information off to a central NATS server that anyone can subscribe to.
1 - Download the latest version here : https://github.com/broderickhyman/albiondata-client/releases

)
Works with informations from Albion Data Client.
In order to redirect the ADC to this page, launch it with -i="websiteadresse/albionDataClient.php"

More on ADC : https://github.com/broderickhyman/albiondata-client

Note on location codes :
$locationsCode = [
	-1 => "Unknown",
	0 => "ThetfordMarket",
	1000 => "LymhurstMarket",
	2000 => "BridgewatchMarket",
	3003 => "BlackMarket",
	3004 => "MartlockMarket",
	3005 => "CaerleonMarket",
	4000 => "FortSterlingMarket",

	4 => "SwampCrossMarket",
	1006 => "ForestCrossMarket",
	2002 => "SteppeCrossMarket",
	3002 => "HighlandCrossMarket",
	4006 => "MountainCrossMarket",
];

TODO List :
Add a cron to call original API to gather more data
Add a tutorial to explain how setup ADC
Add a cron to clean duplicated data
Add a script to gather prices stats on items (min / max / median on 7 / 15 / 30 past days)
Add an option to return only profitable refine / craft