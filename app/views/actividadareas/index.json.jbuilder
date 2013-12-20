json.array!(@actividadareas) do |actividadarea|
  json.extract! actividadarea, :nombre, :observaciones, :fechacreacion, :fechadeshabilitacion
  json.url actividadarea_url(actividadarea, format: :json)
end
