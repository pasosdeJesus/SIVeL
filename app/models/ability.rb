class Ability
  include CanCan::Ability

  ROLADMIN  = 1
  ROLINV    = 2
  ROLDIR    = 3
  ROLCOOR   = 4
  ROLANALI  = 5
  ROLSIST   = 6

  ROLES = [["Administrador", ROLADMIN], ["Invitado Nacional", ROLINV], 
      ["Director Nacional", ROLDIR], ["Coordinador oficina", ROLCOOR], 
      ["Analista nacional", ROLANALI], ["Sistematizador oficina", ROLSIST]]

  @@tablasbasicas = [
    'actividadarea', 'actividadoficio', 'aslegal', 'aspsicosocial', 'ayudasjr', 
    'categoria', 'causaref', 'clase', 
    'departamento', 
    'emprendimiento', 'escolaridad', 'estadocivil', 'etiqueta', 'etnia', 
    'idioma', 'iglesia', 
    'maternidad', 'municipio', 
    'pais', 'presponsable', 'profesion', 'proteccion', 
    'rangoedad', 'rangoedadac', 'regionsjr', 'rolfamilia', 
    'statusmigratorio', 'supracategoria',
    'tclase', 'tsitio', 'tviolencia'
  ]

  def self.tablasbasicas
    @@tablasbasicas
  end

  def initialize(user)
	  #debugger
	  if !user.nil? && !user.rol.nil? && user.rol > 0 then
		  can :read, :all
	  end
	  if !user.nil? && !user.rol.nil? && user.rol == 1 then
		  can :manage, :all
	  end
    # Define abilities for the passed in user here. For example:
    #
    #   user ||= User.new # guest user (not logged in)
    #   if user.admin?
    #     can :manage, :all
    #   else
    #     can :read, :all
    #   end
    #
    # The first argument to `can` is the action you are giving the user 
    # permission to do.
    # If you pass :manage it will apply to every action. Other common actions
    # here are :read, :create, :update and :destroy.
    #
    # The second argument is the resource the user can perform the action on. 
    # If you pass :all it will apply to every resource. Otherwise pass a Ruby
    # class of the resource.
    #
    # The third argument is an optional hash of conditions to further filter the
    # objects.
    # For example, here the user can only update published articles.
    #
    #   can :update, Article, :published => true
    #
    # See the wiki for details:
    # https://github.com/ryanb/cancan/wiki/Defining-Abilities
  end
end
