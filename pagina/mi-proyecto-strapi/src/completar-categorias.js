'use strict';

// Clasificar productos generales y quitar Fragancias. Respaldo previo privado.
module.exports = async function completarCategorias(strapi) {
  const store = strapi.store({type:'core',name:'catalogo'});
  if (await store.get({key:'arbol-v2'})) return;
  const categories = strapi.db.query('api::categoria.categoria');
  const products = strapi.db.query('api::producto.producto');
  let records = await categories.findMany();
  const find = name => records.filter(c => c.Nombre === name);
  const destination = (name,published) => find(name).find(c => Boolean(c.publishedAt) === published) || find(name)[0];
  const additions = [
    ['Limpiaparabrisas','Limpieza · productos'],
    ['Esponjas y aplicadores','Limpieza · accesorios'],
    ['Mangueras y accesorios de lavado','Limpieza · accesorios'],
    ['Plumeros y limpiavidrios','Limpieza · accesorios'],
    ['Infladores','Neumáticos e inflado'],
    ['Válvulas y selladores','Neumáticos e inflado'],
    ['Destornilladores','Herramientas y elevación'],
    ['Tensores y redes','Sujeción y seguridad']
  ];
  for (const [,parent] of additions) if (!find(parent).length) throw new Error('Falta grupo '+parent);
  const before = await products.count({where:{publishedAt:{$notNull:true}}});
  await strapi.db.transaction(async () => {
    for (const [name,parent] of additions) {
      if (!find(name).length) {
        await strapi.documents('api::categoria.categoria').create({
          status:'published',data:{Nombre:name,categoria:destination(parent,true).documentId}
        });
      }
    }
    records = await categories.findMany();
    const rules = [
      ['Limpieza · productos', p => {
        if (/SHAMPOO|WASH/i.test(p.Nombre)) return 'Shampoos';
        if (/SILICONA|REVIVIDOR/i.test(p.Nombre)) return 'Siliconas';
        if (/DESENGRASANTE|LAVAMOTORES/i.test(p.Nombre)) return 'Desengrasantes';
        if (/TAPIZADO/i.test(p.Nombre)) return 'Limpieza de interiores';
        if (/PARABRISAS/i.test(p.Nombre)) return 'Limpiaparabrisas';
        throw new Error('Producto sin clasificación: '+p.Nombre);
      }],
      ['Limpieza · accesorios', p => {
        if (/ASPIRADORA/i.test(p.Nombre)) return 'Aspiradoras';
        if (/KIT LIMPIEZA/i.test(p.Nombre)) return 'Kits de limpieza';
        if (/CEPILLO|BROCHA/i.test(p.Nombre)) return 'Cepillos';
        if (/PISTOLA|LANZA ESPUMA/i.test(p.Nombre)) return 'Mangueras y accesorios de lavado';
        if (/PLUMERO|SECA VIDRIOS/i.test(p.Nombre)) return 'Plumeros y limpiavidrios';
        if (/ESPONJA|DISCOS DE MICROFIBRA/i.test(p.Nombre)) return 'Esponjas y aplicadores';
        if (/PAÑO|PANO|MICROFIBRA|CHAMOIS/i.test(p.Nombre)) return 'Paños';
        throw new Error('Producto sin clasificación: '+p.Nombre);
      }],
      ['Herramientas y elevación', p => /CABALLETE/i.test(p.Nombre) ? 'Caballetes' : /CRIQUE/i.test(p.Nombre) ? 'Criquet hidraulico' : /LLAVE/i.test(p.Nombre) ? 'Llaves' : 'Destornilladores'],
      ['Neumáticos e inflado', p => /COMPRESOR/i.test(p.Nombre) ? 'Compresores' : /CALIBRE/i.test(p.Nombre) ? 'Calibres' : /TAPITA|SELLADOR/i.test(p.Nombre) ? 'Válvulas y selladores' : 'Infladores'],
      ['Sujeción y seguridad', p => /BOTIQUIN/i.test(p.Nombre) ? 'Botiquines' : /LINGA/i.test(p.Nombre) ? 'Lingas' : 'Tensores y redes'],
      ['Fragancias', () => 'Aromatizantes']
    ];
    for (const [source,resolve] of rules) {
      for (const category of find(source)) {
        const assigned = await products.findMany({where:{categoria:{id:category.id}}});
        for (const product of assigned) {
          const target = destination(resolve(product),Boolean(product.publishedAt));
          if (!target) throw new Error('No existe destino para '+product.Nombre);
          await products.update({where:{id:product.id},data:{categoria:target.id}});
        }
      }
    }
    // El usuario pidió borrar esta categoría: sus productos ya fueron trasladados.
    for (const documentId of new Set(find('Fragancias').map(c => c.documentId))) {
      await strapi.documents('api::categoria.categoria').delete({documentId});
    }
    const after = await products.count({where:{publishedAt:{$notNull:true}}});
    if (after !== before) throw new Error('La cantidad de productos cambió');
    await store.set({key:'arbol-v2',value:{appliedAt:new Date().toISOString(),products:after}});
  });
  strapi.log.info('Clasificación completa; Fragancias eliminada; productos conservados.');
};
