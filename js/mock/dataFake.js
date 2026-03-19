const DATA = {
himnos: [
{ titulo: 'Santo, Santo, Santo' },
{ titulo: 'Castillo Fuerte' }
],
alabanza: [
{ titulo: 'Te Exaltamos' },
{ titulo: 'Grande es Él' }
],
adoracion: [
{ titulo: 'Aquí Estoy' },
{ titulo: 'Renuévame' }
]
};

export function obtenerItemsFake(categoria) {
return DATA[categoria] || [];
}