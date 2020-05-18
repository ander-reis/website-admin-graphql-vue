import apollo from "../plugins/apollo";
import { from } from 'rxjs'
import { map } from 'rxjs/operators'

/*
 * notícia graphql
 */
import NoticiasQuery from '../graphql/noticias/NoticiasQuery.graphql'
import NoticiaCreate from '../graphql/noticias/NoticiasCreate.graphql'
import NoticiaUpdate from '../graphql/noticias/NoticiasUpdate.graphql'

/*
 * noticias categorias graphql
 */
import NoticiaCategoriaQuery from '../graphql/noticias/NoticiaCategoriaQuery.graphql'
import NoticiaCategoriaCreate from '../graphql/noticias/NoticiaCategoriaCreate.graphql'
import NoticiaCategoriaUpdate from '../graphql/noticias/NoticiaCategoriaUpdate.graphql'
import NoticiaCategoriaDelete from '../graphql/noticias/NoticiaCategoriaDelete.graphql'
import {connectableObservableDescriptor} from "rxjs/internal/observable/ConnectableObservable";
import {requiredSubselectionMessage} from "graphql/validation/rules/ScalarLeafs";

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
            // insere noticia no cache
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
                console.log('Query "notícias" não foi criada ainda!', e)
            }
        }
    })
    return response.data.create_noticia
}

const noticiaUpdate = async (variables) => {
    console.log(variables)
    const response = await apollo.mutate({
        mutation: NoticiaUpdate,
        variables,
        update: (proxy, { data: { update_noticia } })  => {
            try {
                // reescreve query cache apollo
                proxy.writeData({
                    query: NoticiasQuery,
                    variables,
                    data: update_noticia
                })
            } catch (e) {
                console.log('Query "notícias" não foi criada ainda!', e)
            }
        }
    })
    return response.data.update_noticia
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
        mutation: NoticiaCategoriaCreate,
        variables,
        update: (proxy, { data: { create_categoria } })  => {

            // console.log('proxy:: ', proxy)
            // console.log('create_categoria:: ', create_categoria)

            // insere categoria no cache
            try {
                // lê query cache apollo
                const categoriasData = proxy.readQuery({
                    query: NoticiaCategoriaQuery,
                    variables
                })

                // console.log('readQuery::', categoriasData)

                // reatribui valores
                categoriasData.noticia_categoria = [...categoriasData.noticia_categoria, create_categoria]

                // console.log('noticia_categoria Array::', categoriasData.noticia_categoria)

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
const noticiasCategoriasDelete = async (categoria) => {
    const response = await apollo.mutate({
        mutation: NoticiaCategoriaDelete,
        variables: categoria,
        update: (proxy) => {

            const categoriasData = proxy.readQuery({
                query: NoticiaCategoriaQuery
            })

            const categorias = categoriasData.noticia_categoria.filter(item => {
                if(item.id !== categoria.id) {
                    return item;
                }
            });

            categoriasData.noticia_categoria = [...categorias]

            proxy.writeQuery({
                query: NoticiaCategoriaQuery,
                data: categoriasData
            })
        }
    })
    //return response.data
}

export default {
    noticiasQuery,
    noticiaCreate,
    noticiaUpdate,
    noticiaCategoriaQuery,
    noticiaCategoriaCreate,
    noticiasCategoriasUpdate,
    noticiasCategoriasDelete,
}
