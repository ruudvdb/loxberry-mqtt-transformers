# loxberry-mqtt-transformers
Custom Loxberry MQTT transformers to use within Loxone.

## json_escape.php
Virtual output from Loxone escapes double quotes. JSON data doesn't like the escape characaters. This transformer will 'unescape' double quotes.
So this transformer will find and replace all `\"` with `"`.

An example the command in Loxone's virtual output: `publish json_escape lg/TV/Set {"Power": false}`

## miboxer_rgbcct.php
MiBoxer lamps use HSL for colors. With this transformer you can control MiBoxer lamps from Loxone. I'm using an ESP32 with software from [esp8266_milight_hub](https://github.com/sidoh/esp8266_milight_hub) to control the lights. You have to use _Loxone's lighting block v2_ and set the output to type **Lumitech DMX**. Now connect the output to a virtual output to send the output to MQTT. 

Here is an example: `publish miboxer_rgbcct milight/commands/0x3E8/rgb_cct/1 <v>`
It supports RGB and tuneable white.
