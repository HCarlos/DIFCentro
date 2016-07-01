
Create or Replace View  _viUsuarios As 
select u.iduser, u.username, u.password, u.apellidos, u.nombres, u.foto, u.username as user,
		u.registro, u.especialidad, u.domicilio, u.colonia, u.idmunicipio, u.idestado,
		u.teloficina, u.telpersonal, u.telfax, u.correoelectronico, 
		u.idemp, e.rs as empresa, e.logo as logoempresa, 
		u.idusernivelacceso,un.nivel_de_acceso, 
		CASE WHEN un.clave IS NOT NULL THEN un.clave ELSE 0 END AS clave,
		u.status_usuario, u.token, u.token_source, u.token_validated, u.registrosporpagina, u.param1
from usuarios u
Left Join empresa e
	On u.idemp = e.idemp
Left Join usuarios_niveldeacceso un
	On u.idusernivelacceso = un.idusernivelacceso



Create or Replace View _vi_Cat_Oficinas As
Select 
	o.idoficina,
	o.iddependencia,
	o.oficina,
	o.status_oficina,
	dep.dependencia,
	o.idemp 
From cat_oficinas o 
Left Join cat_dependencias dep 
	On o.iddependencia = dep.iddependencia	



Create or Replace View _vi_Cat_Clases As
Select 
	cls.idclase,
	cls.idoficina,
	cls.cuenta,
	cls.clase,
	cls.status_clase,
	o.oficina,
	cls.idemp
From cat_clases cls
Left Join cat_oficinas o  
	On cls.idoficina = o.idoficina	


Create or Replace View _vi_Cat_SubClases As
Select 
	sc.idsubclase,
	sc.subclase,
	sc.idclase,
	sc.cuenta,
	sc.status_subclase,
	cls.clase,
	sc.idemp
From cat_subclases sc 
Left Join cat_clases cls   
	On sc.idclase = cls.idclase	



Create or Replace View _vi_Cat_Ingresos As
Select 
	ing.idingreso, 
	ing.idsubclase, 
	ing.tipo, 
	ing.porcentaje_ingreso, 
	ing.dsm_min, 
	ing.dsm_max, 
	ing.costo_min, 
	ing.costo_max, 
	ing.clave, 
	ing.ingreso, 
	ing.ano, 
	ing.status_ingreso,
	sc.subclase,
	sc.idclase,
	sc.status_subclase,
	cls.idoficina,
	cls.clase,
	cls.status_clase,
	o.iddependencia,
	o.oficina,
	o.status_oficina,
	dep.dependencia, 
	dep.status_dependencia,	
	ing.idemp
From cat_ingresos ing  
Right Join cat_subclases sc 
	On ing.idsubclase = sc.idsubclase 
Left Join cat_clases cls 
	On sc.idclase = cls.idclase	
Left Join cat_oficinas o 
	On cls.idoficina = o.idoficina	
Left Join cat_dependencias dep 
	On o.iddependencia = dep.iddependencia	



Create or Replace View _vi_Usuario_Dep As
Select 
	du.iddepuser, 
	du.iddependencia,
	d.dependencia, 
	du.iduser, 
	u.username,
	concat(u.apellidos,' ',u.nombres) as usuario,
	du.idemp
from usuarios_dep du 
Left Join usuarios u
	On du.iduser = u.iduser 
Left Join cat_dependencias d 
	On du.iddependencia = d.iddependencia		


Create or Replace View _vi_Dep_User As
Select 
	du.iddepuser, 
	du.iddependencia,
	ing.dependencia, 
	du.iduser, 
	u.username,
	concat(u.apellidos,' ',u.nombres) as usuario,
	ing.idingreso,
	ing.ingreso,
	ing.tipo,
	ing.idsubclase, 
	ing.porcentaje_ingreso, 
	ing.dsm_min, 
	ing.dsm_max, 
	ing.costo_min, 
	ing.costo_max, 
	ing.clave, 
	ing.ano, 
	ing.status_ingreso,
	du.idemp
from usuarios_dep du 
Left Join usuarios u
	On du.iduser = u.iduser 
Left Join _vi_Cat_Ingresos ing 
	On du.iddependencia = ing.iddependencia		


Create or Replace View _vi_Contribuciones As 
Select 
	contrib.idcontribucion, 
	contrib.idingreso, 
	contrib.cantidad, 
	contrib.precio_unitario, 
	contrib.subtotal, 
	contrib.descuento, 
	contrib.total, 
	contrib.contribuyente, 
	contrib.observaciones,
	contrib.tokenpay,
	contrib.idtransacpay,
	contrib.fecha, 
	DATE_FORMAT(contrib.fecha, '%d-%m-%Y') as cFecha, 	
	contrib.generado_por, 
	contrib.cobrado_por, 
	contrib.fecha_cobro, 
	contrib.status_contribucion, 
	ing.ingreso,
	ing.tipo,
	ing.idsubclase, 
	ing.porcentaje_ingreso, 
	ing.dsm_min, 
	ing.dsm_max, 
	ing.costo_min, 
	ing.costo_max, 
	ing.clave, 
	ing.ano, 
	ing.status_ingreso,
	genpor.username as creado_por_usuario,	
	cobpor.username as cobrado_por_usuario,	
	concat(cobpor.apellidos,' ',cobpor.nombres,' (',cobpor.username,')') as cobrado_por_usuario_nombre_completo,	
	contrib.idemp
From contribuciones contrib
Left Join _vi_Cat_Ingresos ing 
	On contrib.idingreso = ing.idingreso		
Left Join _viUsuarios genpor 
	On contrib.generado_por = genpor.iduser
Left Join _viUsuarios cobpor 
	On contrib.cobrado_por = cobpor.iduser	

Create or Replace View _vi_Ing_to_Ing As 
Select 
	ii.idingtoiding, 
	ii.idingreso_a, 
	ii.idingreso_b, 
	ii.importe_ingreso_a, 
	ii.importe_ingreso_b, 
	ii.status_ingreso_a_ingreso, 
	ii.idemp,
	ia.ingreso as ingreso_a,
	ia.tipo as tipo_a,
	ia.idsubclase as idsubclase_a, 
	ia.porcentaje_ingreso as porcentaje_ingreso_a, 
	ia.dsm_min as dsm_min_a, 
	ia.dsm_max as dsm_max_a, 
	ia.costo_min as costo_min_a, 
	ia.costo_max as costo_max_a, 
	ia.clave as clave_a, 
	ia.ano as ano_a, 
	ia.status_ingreso as status_ingreso_a,
	ib.ingreso as ingreso_b,
	ib.tipo as tipo_b,
	ib.idsubclase as idsubclase_b, 
	ib.porcentaje_ingreso as porcentaje_ingreso_b, 
	ib.dsm_min as dsm_min_b, 
	ib.dsm_max as dsm_max_b, 
	ib.costo_min as costo_min_b, 
	ib.costo_max as costo_max_b, 
	ib.clave as clave_b, 
	ib.ano as ano_b, 
	ib.status_ingreso as status_ingreso_b
From  asoc_ingreso_a_ingreso ii
Left Join _vi_Cat_Ingresos ia 
	On ii.idingreso_a = ia.idingreso		
Left Join _vi_Cat_Ingresos ib 
	On ii.idingreso_b = ib.idingreso		


Create or Replace View _vi_SubCat_Ben As
Select 
sb.idsubcatben, 
sb.idbeneficio, 
b.beneficio,
sb.idsubcategoria,
sc.subcategoria, 
sb.status_subcat_ben, 
sb.idemp
From asoc_subcat_beneficio sb
Left Join cat_beneficios b 
	On sb.idbeneficio = b.idbeneficio 
Left Join cat_subcategorias sc 
	On sb.idsubcategoria = sc.idsubcategoria
Where sb.status_subcat_ben = 1 
	 

Create or Replace View _vi_Localidades	As 
Select 
	loc.iddelegacion, 
	del.delegacion,
	loc.idcategorialocalidad, 
	ccl.categoria,
	loc.idlocalidad, 
	loc.localidad,
	del.zona,
	del.ruta,
	del.distrito,
	del.seccion_electoral,
	del.mapa_localidad,
	del.riesgo_comunidad,
	del.idplantapotabilizadora,
	del.grado_marginacion
From cat_localidades loc 
Left Join cat_delegaciones del
	On loc.iddelegacion = del.iddelegacion
Left Join cat_categorias_localidades ccl
	On loc.idcategorialocalidad = ccl.idcategorialocalidad


Create or Replace View _vi_Beneficiarios As 
Select 
	ben.idbeneficiario, 
	ben.ap_paterno, 
	ben.ap_materno, 
	ben.nombre, 
	concat(ben.ap_paterno,' ',ben.ap_materno,' ',ben.nombre) as beneficiario, 
	ben.telefono, 
	ben.correo_electronico, 
	ben.sexo, 
	ben.idlocalidad, 
	l.delegacion,
	l.categoria,
	l.localidad,
	ben.status_beneficiario, 
	ben.idemp
From cat_beneficiarios ben
Left Join _vi_Localidades l 
	On ben.idlocalidad = l.idlocalidad

Create or Replace View _vi_Beneficios_Otorgados	As 
Select 
	bo.idbeneficiootorgado, 
	bo.idbeneficiario, 
	ben.beneficiario,
	ben.delegacion,
	ben.categoria,
	ben.idlocalidad,
	ben.localidad,
	bo.idsubcatben, 
	sb.idbeneficio,
	sb.beneficio,
	sb.subcategoria,
	bo.cantidad,
	DATE_FORMAT(bo.fecha,"%d-%m-%Y") as fecha, 
	bo.observaciones, 
	bo.status_beneficio_otorgado, 
	bo.idemp
From beneficios_otorgados bo	
Left Join _vi_Beneficiarios ben 
	On bo.idbeneficiario = ben.idbeneficiario
Left Join _vi_SubCat_Ben sb 
	On bo.idsubcatben = sb.idsubcatben

