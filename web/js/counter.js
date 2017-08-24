function counter(d,h,m,s,divId)
{
	var e = document.getElementById(divId);

	if (s < 0)
	{
		clearTimeout(timer);
		m = m - 1;
		s = 59;
	}

	if (m < 0)
	{
		clearTimeout(timer);
		h = h - 1;
		m = 59;
	}

	if (h < 0)
	{
		clearTimeout(timer);
		d = d - 1;
		h = 23;
	}

	if (d < 0)
	{
		clearTimeout(timer);
		d = 0;
		h = 0;
		m = 0;
		s = 0;
	}

	st = s;
	mt = m;
	ht = h;
	dt = d;

	if (s < 10)
	{
		st = "0"+s;
	}
	if (m < 10)
	{
		mt = "0"+m;
	}
	if (h < 10)
	{
		ht = "0"+h;
	}
	if (d < 10)
	{
		dt = "0"+d;
	}

	e.innerHTML = dt+":"+ht+":"+mt+":"+st;

	s--;
	var timer = setTimeout('counter('+d+','+h+','+m+','+s+',"'+divId+'")',1000);
}
