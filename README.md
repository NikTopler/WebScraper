# WebScraper
A lightweight scripts that scrapes article data from 24ur.com.

* [Installation](#installation)
* [Usage](#usage)
* [Output](#output)
* [Upkeep](#Upkeep)
* [Issues](#issues)

## Usage
#### XAMPP 
Add [24ur.com](https://github.com/NikTopler/WebScraper_24ur.com) to `htdocs` folder and start localhost

#### Heroku
Follow [Heroku](https://devcenter.heroku.com/articles/getting-started-with-php) instructions



## Output
The output is an array of JSON objects, with each article following the structure below:
```json
	[
        {
        "title": "Tisoči spremljali neposredni prenos rušitve nekdanje Trumpove igralnice",
        "info": {
           "city": "Trenton",
           "date": "17.02.2021",
           "time": "19:12"
        },
        "authors": [
            {
              "name": "Tina Švajger",
              "url": "http://24ur.com/iskanje?q=avtor:%22Tina%20%C5%A0vajger%22"
            }
        ],
        "subtitle": "Z nadzorovano eksplozijo so porušili nekdanjo igralnico v lasti Donalda Trumpa, ki je že več let nezadržno propadala.",
        "content": "Trumpova Plaza je bila nekaj časa najuspešnejša igralnica v Atlantic Cityju, pojavila se je celo v filmski uspešnici Oceanovih 11.",
        "urlToImage": [ "https://images.24ur.com/media/images/1106xX/Feb2021/c557a0ee2373ab3bf1ac_62519903.jpg?v=edbb" ],
        "urlToArticle": "http://24ur.com/novice/tujina/tisoci-spremljali-neposredni-prenos-rusitve-nekdanje-trumpove-igralnice.html"
        },
        {
            ...
        }
    ]

```
## Upkeep
Please note that this is a web-scraper, which relies on DOM selectors, so any fundamental changes in the markup on the 24ur.com site will probably break this tool. I'll try my best to keep it up-to-date, but many of these changes will be silent. Feel free to submit an issue if it stops working.

## Issues
Feel free to [submit a PR](https://github.com/niktopler/WebScraper_24ur.com/pulls) if you've fixed an open issue. Thank you.