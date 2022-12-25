import index from '../pages/items/index'
import new_account from '../pages/accounts/new_account'
import employee_account from '../pages/accounts/employee_accounts'
import user_account from '../pages/accounts/user_accounts'
// import add from '@/pages/Accounts/Add'
// import edit from '@/pages/Accounts/Edit'
// import show from '@/pages/Accounts/Show'

let uri = 'accounts'

export default [
    {
    path: `/items`,
    name: `items`,
    component: index,
    // children: [
    //   {
    //     path: `/accounts/add-new-account`,
    //     name: `new_account`,
    //     component: new_account
    //   },
    //   {
    //     path: `/accounts/employee_account`,
    //     name: `employee_account`,
    //     component: employee_account
    //   },
    //   {
    //     path: `/accounts/user_account`,
    //     name: `user_account`,
    //     component: user_account
    //   },
    // ],
   }
]