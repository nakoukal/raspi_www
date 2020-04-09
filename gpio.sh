#!/bin/sh

echo "17" > /sys/class/gpio/export
echo "18" > /sys/class/gpio/export
echo "27" > /sys/class/gpio/export
echo "22" > /sys/class/gpio/export
echo "23" > /sys/class/gpio/export
echo "24" > /sys/class/gpio/export
echo "25" > /sys/class/gpio/export
echo "5" > /sys/class/gpio/export
echo "6" > /sys/class/gpio/export
echo "12" > /sys/class/gpio/export
echo "13" > /sys/class/gpio/export
echo "19" > /sys/class/gpio/export
echo "16" > /sys/class/gpio/export
echo "26" > /sys/class/gpio/export
echo "20" > /sys/class/gpio/export
echo "21" > /sys/class/gpio/export

echo "out" > /sys/class/gpio/gpio17/direction
echo "out" > /sys/class/gpio/gpio18/direction
echo "out" > /sys/class/gpio/gpio27/direction
echo "out" > /sys/class/gpio/gpio22/direction
echo "out" > /sys/class/gpio/gpio23/direction
echo "out" > /sys/class/gpio/gpio24/direction
echo "out" > /sys/class/gpio/gpio25/direction
echo "out" > /sys/class/gpio/gpio5/direction
echo "out" > /sys/class/gpio/gpio6/direction
echo "out" > /sys/class/gpio/gpio12/direction
echo "out" > /sys/class/gpio/gpio13/direction
echo "out" > /sys/class/gpio/gpio19/direction
echo "in" > /sys/class/gpio/gpio16/direction
echo "out" > /sys/class/gpio/gpio26/direction
echo "out" > /sys/class/gpio/gpio20/direction
echo "out" > /sys/class/gpio/gpio21/direction

chmod 666 /sys/class/gpio/gpio17/value
chmod 666 /sys/class/gpio/gpio18/value
chmod 666 /sys/class/gpio/gpio27/value
chmod 666 /sys/class/gpio/gpio22/value
chmod 666 /sys/class/gpio/gpio23/value
chmod 666 /sys/class/gpio/gpio24/value
chmod 666 /sys/class/gpio/gpio25/value
chmod 666 /sys/class/gpio/gpio5/value
chmod 666 /sys/class/gpio/gpio6/value
chmod 666 /sys/class/gpio/gpio12/value
chmod 666 /sys/class/gpio/gpio13/value
chmod 666 /sys/class/gpio/gpio19/value
chmod 666 /sys/class/gpio/gpio16/value
chmod 666 /sys/class/gpio/gpio26/value
chmod 666 /sys/class/gpio/gpio20/value
chmod 666 /sys/class/gpio/gpio21/value

chmod 666 /sys/class/gpio/gpio17/direction
chmod 666 /sys/class/gpio/gpio18/direction
chmod 666 /sys/class/gpio/gpio27/direction
chmod 666 /sys/class/gpio/gpio22/direction
chmod 666 /sys/class/gpio/gpio23/direction
chmod 666 /sys/class/gpio/gpio24/direction
chmod 666 /sys/class/gpio/gpio25/direction
chmod 666 /sys/class/gpio/gpio5/direction
chmod 666 /sys/class/gpio/gpio6/direction
chmod 666 /sys/class/gpio/gpio12/direction
chmod 666 /sys/class/gpio/gpio13/direction
chmod 666 /sys/class/gpio/gpio19/direction
chmod 666 /sys/class/gpio/gpio16/direction
chmod 666 /sys/class/gpio/gpio26/direction
chmod 666 /sys/class/gpio/gpio20/direction
chmod 666 /sys/class/gpio/gpio21/direction
