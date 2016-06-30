// JavaScript Document Registry

$("#frmLogin").on("submit", function(event) {
	event.preventDefault();
	var queryString = $(this).serialize();
	$.post(obj.getValue(0) + "getLoginUser/", {
			data: queryString
		},
		function(json) {
			//alert(json[0].msg);
			if (json[0].msg == "OK") {
				if (!sessionStorage.Id || (sessionStorage.Id !== json[0].data)) {
					sessionStorage.Id = json[0].data;
					sessionStorage.name = json[0].label;
					localStorage.nc = json[0].label;
					var xim = json[0].data.split('|');
					localStorage.IdUser = parseInt(xim[0]);
					localStorage.IdEmp = parseInt(xim[2]);
					localStorage.Empresa = xim[3];
					localStorage.IdUserNivelAcceso = parseInt(xim[4]);
					localStorage.TRPP = parseInt(xim[5]); //registrosporpagina
					//alert(localStorage.IdUserNivelAcceso);
					if (parseInt(localStorage.IdUserNivelAcceso,0) <= 100){
						localStorage.ClaveNivelAcceso = parseInt(xim[6])==null?0:parseInt(xim[6]);
					}else{
						localStorage.ClaveNivelAcceso = 0;
					}
					localStorage.Param1 = xim[7] == ''?'0':xim[7]; //param1
				}
                window.location.href = obj.getValue(0) + "dashboard/";


			} else {
				alert(json[0].msg);

			}
		}, "json");
});
