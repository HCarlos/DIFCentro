//var oUser = new Object();

function oObject() {
	var doctoper = [];
	var keyLatLon = [8, 9, 16, 46, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 110, 171, 187, 221];
	var oUser = [0, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false,
		false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, 0
	];

	var keyUP0 = [3, 4, 20, 999, 1000];
	var keyUP1 = [1, 1000];
	var keyUP2 = [2, 1000];
	var keyUP3 = [3, 1000];
	var keyUP6 = [6];
	var keyUP7 = [8,9,10,11,12];
	var keyUP8 = [888];
	var minHeight = 0;
	var pURLS = ["difcentro.tecnointel.mx"];
	var pHost = ["http://difcentro.tecnointel.mx.mx/", /iphone|ipad|ipod|android/i.test(navigator.userAgent), false, /msie\s6/i.test(navigator.userAgent), "http://187.217.204.100:1803"];
	// var pHost = ["http://platsource.mx/", /iphone|ipad|ipod|android/i.test(navigator.userAgent), false, /msie\s6/i.test(navigator.userAgent), "http://187.157.37.204:8080"];

	var sharedUser = ["none" ];
	var deletePoint = ["none"];
	var mes3 = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
	var cat = [];
	var index = 99999;
	var height = 100;
	var sep = "_devch_";
	var newPos = "";
	var dom = "";
	var id = 0;
	var lat = 0;
	var lon = 0;
	var init = false;
	var from = 0;
	var initFormResponse = false;
	var isTimeLine = false;

	var getInstance = function() {
		if (!oObject.singletonInstance) {
			oObject.singletonInstance = createInstance();
		}
		return oObject.singletonInstance;
	};

	var createInstance = function() {
		return {
			setDP: function(name) {
				doctoper.push(name);
				return this.getDP();
			},
			getDP: function() {
				return doctoper;
			},
			setHost: function() {
				var url = window.location.host;
				var x = pURLS.indexOf(url);
				if (x == -1){
					url = pURLS[0];
				}else{
					url = pURLS[x];
				}
				pHost[0] = "http://"+url+"/";
			},
			getValue: function(i) {
				return pHost[i];
			},
			setUser: function(i, value) {
				oUser[i] = value;
			},
			getUser: function(i) {
				return oUser[i];
			},
			setMinHeight: function(value) {
				minHeight = value;
			},
			getMinHeight: function() {
				return minHeight;
			},
			setLat: function(value) {
				lat = value;
			},
			getLat: function(value) {
				return lat;
			},
			setLon: function(value) {
				lon = value;
			},
			getLon: function(value) {
				return lon;
			},
			findData: function(value) {
				return sharedUser.indexOf(value);
			},
			findUserDeleteAutority: function(value) {
				return deletePoint.indexOf(value);
			},
			setIcon: function(value) {
				index = value;
			},
			getIcon: function(value) {
				return index;
			},
			getMes3: function(index) {
				return mes3[index];
			},
			getFrom: function(value) {
				return from;
			},
			setFrom: function(value) {
				from = value;
			},
			getFormResponse: function(value) {
				return initFormResponse;
			},
			setFormResponse: function(value) {
				initFormResponse = value;
			},
			getIsTimeLine: function(value) {
				return isTimeLine;
			},
			setIsTimeLine: function(value) {
				isTimeLine = value;
			},
			getDateToday: function() {
				var today = new Date();
				var dd = today.getDate();
				var mm = today.getMonth() + 1; //January is 0!
				var yyyy = today.getFullYear();

				if (dd < 10) {
					dd = '0' + dd;
				}

				if (mm < 10) {
					mm = '0' + mm;
				}

				today = dd + '-' + mm + '-' + yyyy;
				return today;
			},
			getkeyLatLon: function(value) {
				return keyLatLon.indexOf(value);
			},
			getkeyUP: function(value, param) {
				var nreturn;
				switch (param) {
					case 0:
						nreturn = keyUP0.indexOf(value);
						break;
					case 1:
						nreturn = keyUP1.indexOf(value);
						break;
					case 2:
						nreturn = keyUP2.indexOf(value);
						break;
					case 3:
						nreturn = keyUP3.indexOf(value);
						break;
					case 6:
						nreturn = keyUP6.indexOf(value);
						break;
					case 8:
					case 9:
					case 10:
					case 11:
					case 12:
						nreturn = keyUP7.indexOf(value);
						break;
					case 888:
						nreturn = keyUP8.indexOf(value);
						break;
					default:
						nreturn = -1;
						break;
				}
				return nreturn;
			},
			getMenuItem: function(value) {
				return arrMenuItems[value];
			},
			getLinkItem: function(value) {
				return arrLinkItems[value];
			},
			getLegendItem: function(value) {
				return arrLegendItems[value];
			},
			setConfig: function(value) {
				doctoper = value;
			},
			getConfig: function(idnivel, tipo) {
				switch (idnivel) {
					case 1:
						key0 = "iva";
						break;
					case 2:
						key0 = "vsm";
						break;
				}

				var vark = key0;

				var x = doctoper.filter(function(obj) {
					return obj.llave == vark;
				});

				return x[0].valor;
			}


		};
	};


	var getInternetExplorerVersion = function() {
		var rv = -1; // Return value assumes failure.
		if (navigator.appName == 'Microsoft Internet Explorer') {
			var ua = navigator.userAgent;
			var re = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
			if (re.exec(ua) != null)
				rv = parseFloat(RegExp.$1);
		}
		return rv;
	};

	function calcPagination(Obj) {
		var ps1 = Obj.totalRegistros / Obj.registrosPorPagina;
		Obj.totalPaginas = (ps1 % 2) === 0 ? ps1 : Math.floor(ps1, 0);
		return Obj;
	}

	var checkVersion = function() {
		var msg = "You're not using Internet Explorer.";
		var ver = getInternetExplorerVersion();

		if (ver > -1) {
			if (ver >= 8.0)
				msg = "You're using a recent copy of Internet Explorer.";
			else
				msg = "You should upgrade your copy of Internet Explorer.";
		}
		alert(msg);
	};

	return getInstance();
}

var obj = new oObject();
obj.setHost();

function getErrorLocation(errorsh) {
	var x = "";
	switch (errorsh.code) {
		case 1:
			x = "Se ha denegado el acceso a la Geolocalización.";
			break;
		case 2:
			x = "Información de Localización No Disponible.";
			break;
		case 3:
			x = "Ha tardado demasiado la información en ser recibida.";
			break;
		case 4:
			x = "Error desconocido.";
			break;
	}
	return x;
}

function isDefined(variable) {
	return (typeof(window[variable]) != "undefined");
}

function getBar(str) {
	var Obj = '';
	var sT = '';
	var arr = str.split(',');
	for (var i = 0; i < arr.length; i++) {
		if (i != arr.length - 1) {
			sT = '				<li>';
			sT += '					<i class="icon-home home-icon"></i>';
			sT += '					<a href="/dashboard/">' + arr[i] + '</a>';
			sT += '';
			sT += '				</li>';
		} else {
			sT = '<li class="active">' + arr[i] + '</li>';
		}
		Obj += sT;
	}
	Obj += '';
	return Obj;
}

function sayNoEval(noEval) {
	return 'Evaluación: <span class="label label-inverse">' + noEval + '</span>';
}

function padl(n, len) {
	var s = n.toString();
	if (s.length < len) {
		s = ('0000000000' + s).slice(-len);
	}
	return s;
}

Number.prototype.toFixedDown = function(digits) {
	var n = this - Math.pow(10, -digits) / 2;
	n += n / Math.pow(2, 53); // added 1360765523: 17.56.toFixedDown(2) === "17.56"
	return n.toFixed(digits);
};

function escapeRegExp(string) {
	return string.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
}

function commaSeparateNumber(val) {
	while (/(\d+)(\d{3})/.test(val.toString())) {
		val = val.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
	}
	return val;
}

function deleteSeparateNumber(val) {
	return val.toString().replace(',', '');
}

function evalAsteriskCapCal(strobj,value) {
	var x0 = strobj.split('-');
	var strr = "";
	switch (x0[0]) {
		case 'idbolpar':
			strr = '100';
			break;
		case 'cond':
			strr = ' 10';
			break;
		case 'ina':
			strr = '';
		case 'obs':
			strr = value;
			break;
	}
	return strr;
}

function str_replace(search, replace, subject, count) {
  //  discuss at: http://phpjs.org/functions/str_replace/
  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Gabriel Paderni
  // improved by: Philip Peterson
  // improved by: Simon Willison (http://simonwillison.net)
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Onno Marsman
  // improved by: Brett Zamir (http://brett-zamir.me)
  //  revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  // bugfixed by: Anton Ongson
  // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // bugfixed by: Oleg Eremeev
  //    input by: Onno Marsman
  //    input by: Brett Zamir (http://brett-zamir.me)
  //    input by: Oleg Eremeev
  //        note: The count parameter must be passed as a string in order
  //        note: to find a global variable in which the result will be given
  //   example 1: str_replace(' ', '.', 'Kevin van Zonneveld');
  //   returns 1: 'Kevin.van.Zonneveld'
  //   example 2: str_replace(['{name}', 'l'], ['hello', 'm'], '{name}, lars');
  //   returns 2: 'hemmo, mars'

  var i = 0,
    j = 0,
    temp = '',
    repl = '',
    sl = 0,
    fl = 0,
    f = [].concat(search),
    r = [].concat(replace),
    s = subject,
    ra = Object.prototype.toString.call(r) === '[object Array]',
    sa = Object.prototype.toString.call(s) === '[object Array]';
  s = [].concat(s);
  if (count) {
    this.window[count] = 0;
  }

  for (i = 0, sl = s.length; i < sl; i++) {
    if (s[i] === '') {
      continue;
    }
    for (j = 0, fl = f.length; j < fl; j++) {
      temp = s[i] + '';
      repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
      s[i] = (temp)
        .split(f[j])
        .join(repl);
      if (count && s[i] !== temp) {
        this.window[count] += (temp.length - s[i].length) / f[j].length;
      }
    }
  }
  return sa ? s : s[0];
}


(function($) {
	$.fn.formatCurrencyON = function(val) {

		while (/(\d+)(\d{3})/.test(val.toString())) {
			val = val.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
		}
		return val;

	};
})(jQuery);

(function($) {
	$.fn.formatCurrencyOFF = function(val) {

		return val.replace(new RegExp(escapeRegExp(','), 'g'), '');

	};
})(jQuery);