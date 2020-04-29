const formatError = message => {
    const messageSplit = message.split(':')
    return messageSplit[messageSplit.length - 1].trim()
}

const errorHandler = (err, vm, info) => {
    console.log('VUE [error handler]:', err, info)
    const jwtErrors = ['jwt malformed', 'jwt expired', 'jwt not active', 'invalid token', 'Login ou senha incorreto']
    if (jwtErrors.some(jwtError => err.message.includes(jwtError))) {
        vm.$router.push({
            path: '/login',
            query: { redirect: vm.$route.path }
        })
    }
}

export {
    formatError,
    errorHandler
}
