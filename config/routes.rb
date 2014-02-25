Sivel2::Application.routes.draw do

  get '/casos/nuevopresponsable' => 'casos#nuevopresponsable'
  get '/casos/lista' => 'casos#lista'
  resources :casos, path_names: { new: 'nuevo', edit: 'edita' }

  resources :actividades, path_names: { new: 'nueva', edit: 'edita' }
  resources :actividadareas, path_names: { new: 'nueva', edit: 'edita' }

  devise_scope :usuario do
    get 'sign_out' => 'devise/sessions#destroy'
  end


  devise_for :usuarios
  resources :usuarios, path_names: { new: 'nuevo', edit: 'edita' } 

  root 'hogar#index'

  get 'nosotros' => 'hogar#nosotros'
  get 'contacto' => 'hogar#contacto'
  get "hogar" => 'hogar#index'

  # The priority is based upon order of creation: first created -> highest priority.
  # See how all your routes lay out with "rake routes".

  # You can have the root of your site routed with "root"
  # root 'welcome#index'

  # Example of regular route:
  #   get 'products/:id' => 'catalog#view'

  # Example of named route that can be invoked with purchase_url(id: product.id)
  #   get 'products/:id/purchase' => 'catalog#purchase', as: :purchase

  # Example resource route (maps HTTP verbs to controller actions automatically):
  #   resources :products

  # Example resource route with options:
  #   resources :products do
  #     member do
  #       get 'short'
  #       post 'toggle'
  #     end
  #
  #     collection do
  #       get 'sold'
  #     end
  #   end

  # Example resource route with sub-resources:
  #   resources :products do
  #     resources :comments, :sales
  #     resource :seller
  #   end

  # Example resource route with more complex sub-resources:
  #   resources :products do
  #     resources :comments
  #     resources :sales do
  #       get 'recent', on: :collection
  #     end
  #   end

  # Example resource route with concerns:
  #   concern :toggleable do
  #     post 'toggle'
  #   end
  #   resources :posts, concerns: :toggleable
  #   resources :photos, concerns: :toggleable

  # Example resource route within a namespace:
  #   namespace :admin do
  #     # Directs /admin/products/* to Admin::ProductsController
  #     # (app/controllers/admin/products_controller.rb)
  #     resources :products
  #   end
end
