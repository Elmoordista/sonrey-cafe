import index from '../pages/accounts/index'
import new_account from '../pages/accounts/new_account'
import employee_account from '../pages/accounts/employee_accounts'
import user_account from '../pages/accounts/user_accounts'
import edit_account from '../pages/accounts/edit_account'
import feedback from '../pages/accounts/feedback'
// import add from '@/pages/Accounts/Add'
// import edit from '@/pages/Accounts/Edit'
// import show from '@/pages/Accounts/Show'

let uri = 'accounts'

export default [
    {
    path: `/accounts`,
    name: `accounts`,
    component: index,
    children: [
      {
        path: `/accounts/add-new-account`,
        name: `new_account`,
        component: new_account
      },
      {
        path: `/accounts/employee_account`,
        name: `employee_account`,
        component: employee_account
      },
      {
        path: `/accounts/feedback`,
        name: `feedback`,
        component: feedback
      },
      {
        path: `/accounts/:id/edit-account`,
        name: `edit_account`,
        component: edit_account
      },
      {
        path: `/accounts/user_account`,
        name: `user_account`,
        component: user_account
      },
    ],
   }
]