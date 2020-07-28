import Catalog from '../../views/Catalog'

export default [
  {
    path: '/',
    redirect: '/catalog'
  },
  {
    path: '/catalog',
    component: Catalog,

    meta: {
      title: 'Каталог',
      layout: 'default'
    }
  }
]
