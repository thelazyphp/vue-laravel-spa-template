import SignIn from '../pages/SignIn'
import SignUp from '../pages/SignUp'
import Posts from '../pages/Posts'
import Groups from '../pages/Groups'
import Profile from '../pages/Profile'

export default [
  {
    path: '/',
    name: 'home',
    redirect: '/posts'
  },
  {
    path: '/sign-in',
    name: 'signIn',
		component: SignIn,

    meta: {
      title: 'Вход',
      layout: 'empty'
    }
  },
  {
    path: '/sign-up',
    name: 'signUp',
		component: SignUp,

    meta: {
      title: 'Регистрация',
      layout: 'empty'
    }
  },
  {
    path: '/posts',
    name: 'posts',
		component: Posts,

    meta: {
      title: 'Посты',
      auth: true,
      layout: 'default'
    }
  },
  {
    path: '/groups',
    name: 'groups',
		component: Groups,

    meta: {
      title: 'Группы',
      auth: true,
      layout: 'default'
    }
  },
  {
    path: '/profile',
    name: 'profile',
		component: Profile,

    meta: {
      title: 'Профиль',
      auth: true,
      layout: 'default'
    }
  }
]
