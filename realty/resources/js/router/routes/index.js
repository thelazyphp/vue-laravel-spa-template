import SignIn from '../../views/SignIn'
import SignUp from '../../views/SignUp'
import Catalog from '../../views/Catalog'
import Profile from '../../views/Profile'
import Employees from '../../views/Employees'
import CreateEmployee from '../../views/CreateEmployee'
import UpdateEmployee from '../../views/UpdateEmployee'

export default [
  {
    path: '/',
    redirect: '/catalog'
  },
  {
    path: '/sign-in',
    component: SignIn,

    meta: {
      layout: 'empty',
      title: 'Вход'
    }
  },
  {
    path: '/sign-up',
    component: SignUp,

    meta: {
      layout: 'empty',
      title: 'Регистрация'
    }
  },
  {
    path: '/catalog',
    component: Catalog,

    meta: {
      layout: 'default',
      auth: true,
      title: 'Каталог'
    }
  },
  {
    path: '/profile',
    component: Profile,

    meta: {
      layout: 'default',
      auth: true,
      title: 'Профиль'
    }
  },
  {
    path: '/employees',
    component: Employees,

    meta: {
      layout: 'default',
      auth: true,
      title: 'Сотрудники'
    }
  },
  {
    path: '/employees/create',
    component: CreateEmployee,

    meta: {
      layout: 'default',
      auth: true,
      title: 'Добавить сотрудника'
    }
  },
  {
    path: '/employees/:id/update',
    component: UpdateEmployee,

    meta: {
      layout: 'default',
      auth: true,
      title: 'Редактировать сотрудника'
    }
  },
  {
    path: '*',
    redirect: '/'
  }
]
