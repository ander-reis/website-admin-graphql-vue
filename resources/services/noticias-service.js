import apollo from "../plugins/apollo";
import { from } from 'rxjs'
import { map } from 'rxjs/operators'

/*
 * notícia graphql
 */
import NoticiasQuery from '../graphql/noticias/NoticiasQuery.graphql'
import NoticiaCreate from '../graphql/noticias/NoticiasCreate.graphql'

/*
 * noticias categorias graphql
 */
import NoticiaCategoriaQuery from '../graphql/noticias/NoticiaCategoriaQuery.graphql'
import NoticiaCategoriaUpdate from '../graphql/noticias/NoticiaCategoriaUpdate.graphql'
import NoticiasCategoriaCreate from '../graphql/noticias/NoticiaCategoriaCreate.graphql'

const noticiasQuery = ({ id } = {}) => {
    const response = apollo.watchQuery({
        query: NoticiasQuery,
        variables: {
            id: id
        }
    });
    return from(response).pipe(map(res => res.data.noticias));
};

const noticiaCreate = async (variables) => {

    const response = await apollo.mutate({
        mutation: NoticiaCreate,
        variables,
        update: (proxy, { data: { create_noticia } })  => {

            // insere categoria no cache
            try {
                // lê query cache apollo
                const noticiasData = proxy.readQuery({
                    query: NoticiasQuery,
                    variables
                })

                // reatribui valores
                noticiasData.noticias = [...noticiasData.noticias, create_noticia]

                // reescreve query cache apollo
                proxy.writeQuery({
                    query: NoticiasQuery,
                    variables,
                    data: noticiasData
                })
            } catch (e) {
                console.log('Query "notícias categorias" não foi criada ainda!', e)
            }
        }
    })
    return response.data.create_noticia
}

const noticiaCategoriaQuery = ({ id } = {}) => {
    const response = apollo.watchQuery({
        query: NoticiaCategoriaQuery,
        variables: id
    })
    return from(response).pipe(map(res => res.data.noticia_categoria))
}

const noticiaCategoriaCreate = async (variables) => {
    const response = await apollo.mutate({
        mutation: NoticiasCategoriaCreate,
        variables,
        update: (proxy, { data: { create_categoria } })  => {

            // insere categoria no cache
            try {
                // lê query cache apollo
                const categoriasData = proxy.readQuery({
                    query: NoticiaCategoriaQuery,
                    variables
                })

                // reatribui valores
                categoriasData.noticia_categoria = [...categoriasData.noticia_categoria, create_categoria]

                // reescreve query cache apollo
                proxy.writeQuery({
                    query: NoticiaCategoriaQuery,
                    variables,
                    data: categoriasData
                })
            } catch (e) {
                console.log('Query "notícias categorias" não foi criada ainda!', e)
            }
        }
    })
    return response.data.create_categoria
}

const noticiasCategoriasUpdate = async (variables) => {
    const response = await apollo.mutate({
        mutation: NoticiaCategoriaUpdate,
        variables,
        update: (proxy, { data: { update_categoria } })  => {
            try {
                // reescreve query cache apollo
                proxy.writeData({
                    query: NoticiaCategoriaQuery,
                    variables,
                    data: update_categoria
                })
            } catch (e) {
                console.log('Query "notícias categorias" não foi criada ainda!', e)
            }
        }
    })
    return response.data.update_categoria
}

export default {
    noticiasQuery,
    noticiaCreate,
    noticiaCategoriaQuery,
    noticiasCategoriasUpdate,
    noticiaCategoriaCreate,
}
