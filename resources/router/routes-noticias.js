const NoticiasComponent = () => import('../js/components/noticias/NoticiasComponent')
const NoticiasCreateCompoent = () => import('../js/components/noticias/NoticiasCreateComponent')
const NoticiasUpdateComponent = () => import('../js/components/noticias/NoticiasUpdateComponent')

const NoticiasCategoriasComponent = () => import('./../js/components/noticias/NoticiasCategoriasComponent')

export default [
    {
        path: '/noticias',
        component: NoticiasComponent,
        meta: { requiresAuth: true },
        name: 'noticias.index',
    },
    {
      path: '/noticias/cadastrar',
      component: NoticiasCreateCompoent,
      meta: { requiresAuth: true },
      name: 'noticias.create'
    },
    {
        path: '/noticias/:id/editar',
        component: NoticiasCreateCompoent,
        meta: { requiresAuth: true },
        name: 'noticias.update',
    },
    {
        path: '/noticias/categorias',
        component: NoticiasCategoriasComponent,
        meta: { requiresAuth: true },
        name: 'noticias.categorias.index'
    }
]
