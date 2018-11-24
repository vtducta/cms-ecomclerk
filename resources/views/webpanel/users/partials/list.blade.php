<?php $i = 1; ?>
@foreach($users as $user)
    <tr class="deleteBox">
        <td>{{ $user->fullName() }}</td>
        <td>
            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
        </td>
        <td>{{ $user->phone }}</td>
        <td>{!! @\App\User::$statusLabel[$user->status] !!}</td>
        <td>{{ $user->created_at }}</td>
        <td>
            <a title="Edit User" href="<?php echo URL::route('webpanel.users.edit', array('id' => encrypt($user->id))); ?>"
               class="btn btn-primary btn-sm btn-circle"><i class="fa fa-pencil"></i></a>
            <a title="Reset Password" href="<?php echo url('webpanel/users/reset-password/' . encrypt($user->id)); ?>"
               class="btn btn-warning btn-sm btn-circle"><i class="fa fa-refresh"></i></a>
            <a title="Delete User"
               href="#"
               class="btn btn-danger btn-sm btn-circle ajaxdelete"
               data-id="<?php echo $user->id; ?>"
               data-url="<?php echo sysUrl('users/delete/'.encrypt($user->id)); ?>"
               data-token="<?php echo urlencode(md5($user->id)); ?>"><i class="fa fa-trash-o"></i></a>
        </td>
    </tr>
    <?php $i++; ?>
@endforeach