#!/usr/bin/python3
from subprocess import Popen, PIPE
from yeelight import Bulb
import time
from globals import *

response = Popen(['owread','28.2E40B4010000/temperature'], stdout=PIPE).communicate()[0].decode('utf-8').strip()
bulb1 = Bulb(BULB_DICT["DIN03"]);
print (response)
if (float(response) > 50.0):
	bulb1.set_hsv(1, 100, 100)
else :  bulb1.set_color_temp(4700)


response = Popen(['owread','28.FF6AB4010000/temperature'], stdout=PIPE).communicate()[0].decode('utf-8').strip()
bulb2 = Bulb(BULB_DICT["DIN01"]);
print (response)
if (float(response) > 50.0):
        bulb2.set_hsv(1, 100, 100)
else :  bulb2.set_color_temp(4700)


response = Popen(['owread','28.D94FB4010000/temperature'], stdout=PIPE).communicate()[0].decode('utf-8').strip()
bulb3 = Bulb(BULB_DICT["DIN02"]);
print (response)
if (float(response) > 50.0):
        bulb3.set_hsv(1, 100, 100)
else :  bulb3.set_color_temp(4700)


time.sleep(30)
bulb1.set_color_temp(4700)
bulb2.set_color_temp(4700)
bulb3.set_color_temp(4700)

