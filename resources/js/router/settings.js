import index from '../pages/settings/index'
import category from '../pages/settings/category'
import roles from '../pages/settings/announcement'
// import add from '@/pages/Accounts/Add'
// import edit from '@/pages/Accounts/Edit'
// import show from '@/pages/Accounts/Show'

let uri = 'purchase'

export default [
    {
    path: `/settings`,
    name: `settings`,
    component: index,
    children: [
      {
        path: `/settings/category`,
        name: `category`,
        component: category
      },
      {
        path: `/settings/announcement`,
        name: `announcement`,
        component: roles
      },
    ],
   }
]