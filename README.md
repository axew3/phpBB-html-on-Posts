# phpBB HTML on posts

## Installation

Copy the extension to phpBB/ext/w3all/htmlposts

Go to "ACP" > "Customise" > "Extensions" and enable the "Html posts" extension.

## How to use

By default, only users that belong to the GroupID 5 (admins) (can be added more into the listener code) will have the capability to post an HTML content that so will be parsed as html.
It is required that the very first post line content, start with (can be changed into the listener code into a custom one) this placeholder: 

    [HTMLMARKUP]
    
or the post content will not be parsed as HTML.
##### NOTE: if we want to display/render (not parse) some active bbcode like [b] or anyone active into our phpBB, then entities should be used 
##### &#91;b&#93; test me i want to show bbcode tags &#91;/b&#93;  <- will return ->  [b] test me i want to show bbcode tags [/b]
#### NOTE: if a single bbcode is found into the post text, then the post will not be parsed as HTML due to generate_text_for_display() that fire after on viewtopic.php

See example: https://www.axew3.com/w3/forums/viewtopic.php?t=1767


## License

[GNU General Public License v2](license.txt)
