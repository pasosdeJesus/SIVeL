Sivel2::Application.routes.draw do

  get '/casos/descarga_anexo/:id' => 'casos#descarga_anexo'
  get '/casos/lista' => 'casos#lista'
  get '/casos/nuevaubicacion' => 'casos#nueva_ubicacion'
  get '/casos/nuevavictima' => 'casos#nueva_victima'
  get '/casos/nuevopresponsable' => 'casos#nuevo_presponsable'
  get 'acercade' => 'hogar#acercade'
  get 'contacto' => 'hogar#contacto'
  get "hogar" => 'hogar#index'

  resources :actividades, path_names: { new: 'nueva', edit: 'edita' }
  resources :casos, path_names: { new: 'nuevo', edit: 'edita' }

  devise_scope :usuario do
    get 'sign_out' => 'devise/sessions#destroy'
  end
  devise_for :usuarios
  resources :usuarios, path_names: { new: 'nuevo', edit: 'edita' } 

  #get 'admin/actividadareas', to: 'actividadareas', as: :actividadareas_path
  namespace :admin do
    Ability.tablasbasicas.each do |t|
        resources t.pluralize.to_sym, path_names: { new: 'nueva', edit: 'edita' }
    end
    #resources :actividadareas, path_names: { new: 'nueva', edit: 'edita' }
    #resources :ayudasestado, path_names: { new: 'nueva', edit: 'edita' }
    #resources :etnia, path_names: { new: 'nueva', edit: 'edita' }
    #resources :tsitio, path_names: { new: 'nueva', edit: 'edita' }
    #resources :clase, path_names: { new: 'nueva', edit: 'edita' }
    #resources :idioma, path_names: { new: 'nueva', edit: 'edita' }
# departamento
# municipio
# actividadoficio
# aslegal
# aspsicosocial
# ayudasjr
# categoria
# causaref
# desplazamiento
# emprendimiento
# escolaridad
# estadocivil
# etiqueta
# iglesia
# maternidad
# pais
# presponsable
# profesion
# proteccion
# regionsjr
# rolfamilia
# statusmigratorio
# tsitio
  end

  root 'hogar#index'

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
