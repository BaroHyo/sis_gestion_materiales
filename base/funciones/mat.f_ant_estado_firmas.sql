CREATE OR REPLACE FUNCTION mat.f_ant_estado_firmas (
  p_id_usuario integer,
  p_id_usuario_ai integer,
  p_usuario_ai varchar,
  p_id_estado_wf integer,
  p_id_proceso_wf integer,
  p_codigo_estado varchar
)
RETURNS boolean AS
$body$
DECLARE
   	v_nombre_funcion   	text;
    v_resp    			varchar;
    v_mensaje 			varchar;
    v_solicitud			record;
BEGIN
  v_nombre_funcion = 'mat.f_ant_estado_firmas';

 select* into v_solicitud
  FROM mat.tsolicitud
  where id_proceso_wf_firma  = p_id_proceso_wf;

 update mat.tsolicitud  set
       id_estado_wf_firma =  p_id_estado_wf,
       estado_firma = p_codigo_estado,
       id_usuario_mod=p_id_usuario,
       id_usuario_ai = p_id_usuario_ai,
       usuario_ai = p_usuario_ai,
       fecha_mod=now()
    where id_proceso_wf_firma = p_id_proceso_wf;

	return true;

EXCEPTION
WHEN OTHERS THEN
			v_resp='';
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje',SQLERRM);
			v_resp = pxp.f_agrega_clave(v_resp,'codigo_error',SQLSTATE);
			v_resp = pxp.f_agrega_clave(v_resp,'procedimientos',v_nombre_funcion);
			raise exception '%',v_resp;
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;