package com.espe.micro_equipos.repositories;

import com.espe.micro_equipos.models.entities.Equipo;
import org.springframework.data.repository.CrudRepository;

public interface EquipoRepository extends CrudRepository<Equipo, Long> {
}
