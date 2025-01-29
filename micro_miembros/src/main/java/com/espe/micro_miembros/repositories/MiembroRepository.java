package com.espe.micro_miembros.repositories;

import com.espe.micro_miembros.models.entities.Miembro;
import org.springframework.data.repository.CrudRepository;

public interface MiembroRepository extends CrudRepository <Miembro, Long> {
}
