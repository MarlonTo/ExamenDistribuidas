package com.espe.micro_equipos.services;

import com.espe.micro_equipos.models.Miembro;
import com.espe.micro_equipos.models.entities.Equipo;

import java.util.List;
import java.util.Optional;

public interface EquipoService {
    List<Equipo> findAll();
    Optional<Equipo> findById(Long id);
    Equipo save(Equipo equipo);
    void deleteById(Long id);

    Optional<Miembro> addMember(Miembro miembro, Long id);
    boolean removeMember(Long equipoId, Long miembroId);
}
