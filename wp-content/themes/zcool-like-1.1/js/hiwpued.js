function getHelloW(){
	var hr = (new Date()).getHours( )
	if (( hr >= 0 ) && (hr <= 4 ))
		return "深夜好！"
	if (( hr >= 4 ) && (hr < 7))
		return "清晨好！"
	if (( hr >= 7 ) && (hr < 12))
		return "早上好！"
	if (( hr >= 12) && (hr <= 13))
		return "中午好！"
	if (( hr >= 13) && (hr <= 17))
		return "下午好！ "
	if (( hr >= 17) && (hr <= 19))
		return "傍晚好！"
	if ((hr >= 19) && (hr <= 23))
		return "晚上好！"
}
document.writeln("Hi，"+getHelloW());