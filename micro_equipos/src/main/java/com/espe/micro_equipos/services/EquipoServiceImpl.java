package com.espe.micro_equipos.services;

import com.espe.micro_equipos.clients.MiembroClientRest;
import com.espe.micro_equipos.models.Miembro;
import com.espe.micro_equipos.models.entities.Equipo;
import com.espe.micro_equipos.models.entities.EquipoMiembro;
import com.espe.micro_equipos.repositories.EquipoRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.List;
import java.util.Optional;

@Service
public class EquipoServiceImpl implements EquipoService {
    @Autowired
    private EquipoRepository repository;

    @Autowired
    private MiembroClientRest clientRest;

    @Override
    public List<Equipo> findAll() {
        return (List<Equipo>) repository.findAll();
    }

    @Override
    public Optional<Equipo> findById(Long id) {
        return repository.findById(id);
    }

    @Override
    public Equipo save(Equipo equipo) {
        return repository.save(equipo);
    }

    @Override
    public void deleteById(Long id) {
        repository.deleteById(id);
    }

    @Override
    public Optional<Miembro> addMember(Miembro miembro, Long id) {
        Optional<Equipo> optional = repository.findById(id);
        if (optional.isPresent()) {
            Miembro miembroTemp = clientRest.findById(miembro.getIdMiembro());

            Equipo equipo = optional.get();
            EquipoMiembro equipoMiembro = new EquipoMiembro();

            equipoMiembro.setMiembroId(miembroTemp.getIdMiembro());

            equipo.addEquipoMiembro(equipoMiembro);
            repository.save(equipo);
            return Optional.of(miembroTemp);
        }
        return Optional.empty();
    }

    @Override
    public boolean removeMember(Long equipoId, Long miembroId) {
        Optional<Equipo> optional = repository.findById(equipoId);
        if (optional.isEmpty()) {
            throw new RuntimeException("No existe el equipo con ID: " + equipoId);
        }

        Equipo equipo = optional.get();
        
        // Verificar si el miembro existe en el equipo
        boolean existeMiembro = equipo.getEquipoMiembros().stream()
                .anyMatch(em -> em.getMiembroId().equals(miembroId));
                
        if (!existeMiembro) {
            throw new RuntimeException("El miembro con ID: " + miembroId + " no pertenece al equipo con ID: " + equipoId);
        }

        // Verificar que no sea el último miembro si es necesario
        if (equipo.getEquipoMiembros().size() <= 1) {
            throw new RuntimeException("No se puede eliminar el último miembro del equipo");
        }

        EquipoMiembro equipoMiembroToRemove = equipo.getEquipoMiembros().stream()
                .filter(em -> em.getMiembroId().equals(miembroId))
                .findFirst()
                .orElseThrow(() -> new RuntimeException("Error al encontrar la relación equipo-miembro"));

        equipo.removeEquipoMiembro(equipoMiembroToRemove);
        repository.save(equipo);
        return true;
    }
}
