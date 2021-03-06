<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\Entity;
use Cake\Utility\Text;

/**
 * AuthUsers Controller
 *
 * @property \App\Model\Table\AuthUsersTable $AuthUsers
 *
 * @method \App\Model\Entity\AuthUser[] paginate($object = null, array $settings = [])
 */
class AuthUsersController extends AppController
{
    /**
     * Initialize method
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['logout']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $authUsers = $this->paginate($this->AuthUsers);

        $this->set(compact('authUsers'));
        $this->set('_serialize', ['authUsers']);
    }

    /**
     * View method
     *
     * @param string|null $id Auth User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $authUser = $this->AuthUsers->get($id, [
            'contain' => []
        ]);

        $loggedInUser = false;
        if ($this->Auth->user('id') == $id) {
            $loggedInUser = true;
        }

        if (!$loggedInUser) {
            $authUser->unsetProperty('api_key_plain');
        }

        $this->set(compact('authUser', 'loggedInUser'));
        $this->set('_serialize', ['authUser']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $authUser = $this->AuthUsers->newEntity();
        if ($this->request->is('post')) {
            $authUser = $this->AuthUsers->patchEntity($authUser, $this->request->getData());
            if ($this->AuthUsers->save($authUser)) {
                $this->Flash->success(__('The auth user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The auth user could not be saved. Please, try again.'));
        }
        $this->set(compact('authUser'));
        $this->set('_serialize', ['authUser']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Auth User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $authUser = $this->AuthUsers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $authUser = $this->AuthUsers->patchEntity($authUser, $this->request->getData());
            if ($this->AuthUsers->save($authUser)) {
                $this->Flash->success(__('The auth user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The auth user could not be saved. Please, try again.'));
        }
        $this->set(compact('authUser'));
        $this->set('_serialize', ['authUser']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Auth User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $authUser = $this->AuthUsers->get($id);
        if ($this->AuthUsers->delete($authUser)) {
            $this->Flash->success(__('The auth user has been deleted.'));
        } else {
            $this->Flash->error(__('The auth user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Login Method
     *
     * @return \Cake\Http\Response|null
     */
    public function login()
    {
        $loggedIn = ( is_numeric($this->Auth->user('id')) );

        if (!$loggedIn) {
            $this->viewBuilder()->setLayout('outside');
        }

        if ($loggedIn) {
            return $this->redirect(['controller' => 'Landing', 'action' => 'welcome']);
        }

        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);

                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error('Your username or password is incorrect.');
        }
    }

    /**
     * Logout Method
     *
     * @return \Cake\Http\Response|null
     */
    public function logout()
    {
        $this->Flash->success('You are now logged out.');

        return $this->redirect($this->Auth->logout());
    }

    /**
     * @param int $authUserId The User Id for the AuthUser to have the API Regenerated
     *
     * @return \Cake\Http\Response|null
     */
    public function regenerateApi($authUserId = null)
    {
        $authUser = $this->AuthUsers->get($authUserId);

        if ($this->request->is('post') && $authUser instanceof Entity) {
            $authUser = $authUser->set('api_key_plain', sha1(Text::uuid()));
            if ($this->AuthUsers->save($authUser)) {
                $this->Flash->success('API Key Updated Successfully.');

                return $this->redirect($this->referer(['action' => 'view', $authUserId]));
            }
        }

        $this->Flash->error('Error Regenerating API Key');

        return $this->redirect($this->referer(['action' => 'view', $authUserId]));
    }
}
