import apollo, { onLogin } from './../plugins/apollo'
import LoginMutation from './../graphql/Login.graphql'
import UserQuery from './../graphql/User.graphql'

const login = async (variables) => {
    const response = await apollo.mutate({
        mutation: LoginMutation,
        variables
    })
    const { login } = response.data
    await onLogin(apollo, login.token)
    return login
}

const user = async (options = {}) => {
    const response = await apollo.query({
        query: UserQuery,
        ...options
    })
    return response.data.user
}

export default {
    login,
    user
}
