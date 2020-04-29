import apollo from "../plugins/apollo";

import Accounts from './../graphql/Accounts.graphql'

const accounts = async () => {
    const response = await apollo.query({
        query: Accounts
    })
    // console.log('accounts: ', response.data.accounts)
    return response.data.accounts
}

export default {
    accounts
}
