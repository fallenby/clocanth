<?php

        namespace App\Controller;

        use Clocanth\Controller;

        use App\Model\User;
        use App\ClocanthView;

        class UserController extends Controller
        {
            public function all()
            {
                $u = new User();
                $users = $u->all();

                return ClocanthView::get('users', array(
                    'users' => $users
                ));
            }
        }
