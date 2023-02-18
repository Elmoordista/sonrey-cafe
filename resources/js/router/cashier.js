import index from '../pages/cashier/index'
import add_order from '../pages/cashier/add_order'
import order_list from '../pages/cashier/order_list'
import user_account from '../pages/accounts/user_accounts'
import edit_account from '../pages/accounts/edit_account'
// import add from '@/pages/Accounts/Add'
// import edit from '@/pages/Accounts/Edit'
// import show from '@/pages/Accounts/Show'

let uri = 'accounts'

export default [
    {
    path: `/cashier`,
    name: `cashier`,
    component: index,
    children: [
      {
        path: `/cashier/add-order`,
        name: `add_order`,
        component: add_order
      },
      {
        path: `/cashier/order_list`,
        name: `order_list`,
        component: order_list
      },
    ],
   }
]