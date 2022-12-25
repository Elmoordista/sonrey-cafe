import index from '../pages/purchase/index'
import history from '../pages/purchase/history'
import total_sales from '../pages/purchase/total_sales'
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
        path: `/accounts/history`,
        name: `history`,
        component: history
      },
      {
        path: `/accounts/total-sales`,
        name: `total_sales`,
        component: total_sales
      },
    ],
   }
]