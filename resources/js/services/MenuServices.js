
import router from '../router';
export class MenuServices {
    getdata() {
        return axios.get('/api/menu-user')
        .then((res) => {
            return {
                tree: res.data.data.tree,
                user: res.data.data.user
            };
        }).catch((error) => {
            console.error('Error fetching data:', error);
            return router.push('login');
        });
    }
}