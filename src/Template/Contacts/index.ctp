<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AuthUser[]|\Cake\Collection\CollectionInterface $authUsers
 */

$this->Breadcrumbs->add(
	__('Contacts'),
	['class' => 'breadcrumb-item active']
);


?>
<div class="row">
    <div class="col" >
        <h2><i class="fas fa-address-card fa-fw"></i> <?= __('Contacts') ?></h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('membership_number') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('first_name') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('last_name') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('wp_role_id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?= $this->Number->format($contact->membership_number, ['pattern' => '#####0']) ?></td>
                        <td><?= h($contact->first_name) ?></td>
                        <td><?= h($contact->last_name) ?></td>
                        <td><?= h($contact->email) ?></td>
                        <td><?= $contact->has('wp_role') ? $this->Html->link($contact->wp_role->wp_role, ['controller' => 'WpRoles', 'action' => 'view', $contact->wp_role->id]) : '' ?></td>
                        <td><?= h($contact->created) ?></td>
                        <td><?= h($contact->modified) ?></td>
                        <td class="actions">
		                    <?= $this->Html->link(__('View'), ['action' => 'view', $contact->id]) ?>
		                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $contact->id]) ?>
		                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $contact->id], ['confirm' => __('Are you sure you want to delete # {0}?', $contact->id)]) ?>
                        </td>
                    </tr>
				<?php endforeach; ?>
                </tbody>
            </table>
            <div class="row">
                <div class="col">
                    <div class="paginator">
                        <ul class="pagination justify-content-left">
					        <?= $this->Paginator->first('<< ' . __('first')) ?>
					        <?= $this->Paginator->prev('< ' . __('previous')) ?>
					        <?= $this->Paginator->numbers() ?>
					        <?= $this->Paginator->next(__('next') . ' >') ?>
					        <?= $this->Paginator->last(__('last') . ' >>') ?>
                        </ul>
                    </div>
                </div>
                <div class="col">
                    <p class="text-muted text-right"><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
