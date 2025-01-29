package com.espe.micro_miembros.services;

import com.espe.micro_miembros.models.entities.Miembro;

import java.util.List;
import java.util.Optional;

public interface MiembroService {
    List<Miembro> findAll();
    Optional<Miembro> findById(Long id);
    Miembro save(Miembro mienmbro);
    void deleteById(Long id);
}
