# Read about factories at https://github.com/thoughtbot/factory_girl

FactoryGirl.define do
  factory :refugio do
    id_caso 1
    fechasalida "2014-03-17"
    id_salida 1
    fechallegad "2014-03-17"
    id_llegada 1
    causa 1
    observaciones "MyString"
  end
end
