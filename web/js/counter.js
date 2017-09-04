function counter(d, h, m, s, divId)
{
	function escapeHtml(unsafe)
	{
		return unsafe
					.replace(/&/g, "&amp;")
					.replace(/</g, "&lt;")
					.replace(/>/g, "&gt;")
					.replace(/"/g, "&quot;")
					.replace(/'/g, "&#039;");
	}

	var e = document.getElementById(divId);
	var timer;

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

	var st = s;
	var mt = m;
	var ht = h;
	var dt = d;

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

	e.innerHTML = escapeHtml(dt+":"+ht+":"+mt+":"+st);

	s--;
	timer = setTimeout("counter("+d+","+h+","+m+","+s+",\""+divId+"\")",1000);
}
