'use strict';
module.exports = {
 register() {},
 async bootstrap({ strapi }) {
  await require('./permisos-pedidos')(strapi);
  await require('./organizar-categorias')(strapi);
  await require('./completar-categorias')(strapi);
  const confirmed = {'TE-006':'IAEL','TV-006':'IAEL','TE-001':'IAEL','SG-998':'IAEL','FP-010':'IAEL','FP-011':'IAEL','VT-017G':'IAEL','RE712':'Revigal'};
  const products = strapi.documents('api::producto.producto');
  for (const [sku, Marca] of Object.entries(confirmed)) {
   const matches = await products.findMany({status:'published',filters:{Descripcion:`Código: ${sku}.`}});
   for (const product of matches) {
    if (product.Marca && product.Marca !== 'Por identificar') continue;
    await products.update({documentId:product.documentId,data:{Marca}});
    await products.publish({documentId:product.documentId});
   }
  }
 }
};