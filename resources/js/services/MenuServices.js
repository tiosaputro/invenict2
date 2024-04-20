export class MenuServices {
    getdata() {
        return axios.get('/api/menu-user')
        .then((res) => {
            return {
                tree: res.data.data.tree,
                user: res.data.data.user
            };
        });
    }
}