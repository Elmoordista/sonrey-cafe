import index from '../pages/purchase/index'
import history from '../pages/purchase/history'
import total_sales from '../pages/purchase/total_sales'
import order_list from '../pages/cashier/order_list'
import order_details from '../pages/cashier/order_details'
// import add from '@/pages/Accounts/Add'
// import edit from '@/pages/Accounts/Edit'
// import show from '@/pages/Accounts/Show'

let uri = 'purchase'

export default [
    {
    path: `/purchase`,
    name: `purchase`,
    component: index,
    children: [
      {
        path: `/purchase/history`,
        name: `history`,
        component: order_list
      },
      {
        path: `/purchase/total-sales`,
        name: `total_sales`,
        component: total_sales
      },
      {
        path: `/purchase/order_list`,
        name: `purchase_list`,
        component: order_list
      },
      {
        path: `/purchase/:id/order_details`,
        name: `purchase_details`,
        component: order_details
      },
    ],
   }
]