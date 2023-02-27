import index from '../pages/kitchen/index'
import orders from '../pages/kitchen/orders'
import employee_account from '../pages/accounts/employee_accounts'
import user_account from '../pages/accounts/user_accounts'
// import add from '@/pages/Accounts/Add'
// import edit from '@/pages/Accounts/Edit'
// import show from '@/pages/Accounts/Show'

let uri = 'accounts'

export default [
    {
    path: `/kitchen`,
    name: `kitchen`,
    component: index,
    children: [
      {
        path: `/kitchen/orders`,
        name: `orders`,
        component: orders
      },
    ],
   }
]