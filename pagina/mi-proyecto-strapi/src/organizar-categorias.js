'use strict';

// Migración única. Antes de aplicarla se respaldó la base en storage (privado).
module.exports = async function organizarCategorias(strapi) {
  const store = strapi.store({ type: 'core', name: 'catalogo' });
  if (await store.get({ key: 'arbol-v1' })) return;
  const categories = strapi.db.query('api::categoria.categoria');
  const products = strapi.db.query('api::producto.producto');
  const records = await categories.findMany({ populate: ['categoria'] });
  const find = name => records.filter(c => c.Nombre === name);
  const destination = (name, published) => find(name).find(c => Boolean(c.publishedAt) === published) || find(name)[0];
  const moves = [
    ['Alfombras','Accesorios interiores'], ['Cubrevolantes','Accesorios interiores'],
    ['Cargadores','Accesorios interiores'], ['Soportes para celular','Accesorios interiores'],
    ['Fundas','Accesorios interiores'], ['Audio y antenas','Accesorios interiores'],
    ['Interior · accesorios','Accesorios interiores','Otros accesorios interiores'],
    ['Motos · fundas','Accesorios','Fundas para motos'],
    ['Electricidad · fusibles','Accesorios','Fusibles'],
    ...['Shampoos','Siliconas','Jabones','Ceras','Pulidores','Desengrasantes','Limpieza de interiores'].map(n => [n,'Limpieza · productos']),
    ...['Cepillos','Paños','Kits de limpieza'].map(n => [n,'Limpieza · accesorios']),
    ...['Compresores','Calibres'].map(n => [n,'Neumáticos e inflado']),
    ...['Llaves','Discos de corte','Caballetes','Criquet hidraulico'].map(n => [n,'Herramientas y elevación']),
    ['Lingas','Sujeción y seguridad']
  ];
  const merges = [['Interior · alfombras','Alfombras'], ['Interior · cubrevolantes','Cubrevolantes'], ['Celulares · soportes y carga','Accesorios interiores']];
  for (const [name,parent] of [...moves,...merges]) {
    if (!find(name).length || !find(parent).length) throw new Error('Falta categoría: '+name+' / '+parent);
  }
  // Una transacción permite deshacer todo si falla una relación.
  await strapi.db.transaction(async () => {
    for (const [name,parent,label] of moves) {
      for (const category of find(name)) {
        await categories.update({where:{id:category.id},data:{Nombre:label || name,categoria:destination(parent,Boolean(category.publishedAt)).id}});
      }
    }
    for (const [source,target] of merges) {
      for (const category of find(source)) {
        const assigned = await products.findMany({where:{categoria:{id:category.id}}});
        for (const product of assigned) {
          const name = source === 'Celulares · soportes y carga' ? (/CARGADOR/i.test(product.Nombre) ? 'Cargadores' : 'Soportes para celular') : target;
          await products.update({where:{id:product.id},data:{categoria:destination(name,Boolean(product.publishedAt)).id}});
        }
      }
      // Se conserva el borrador de las categorías duplicadas para poder recuperarlas.
      for (const documentId of new Set(find(source).map(c => c.documentId))) {
        await strapi.documents('api::categoria.categoria').unpublish({documentId});
      }
    }
    await store.set({key:'arbol-v1',value:{appliedAt:new Date().toISOString()}});
  });
  strapi.log.info('Categorías agrupadas; productos conservados.');
};
