package com.espe.micro_miembros.services;

import com.espe.micro_miembros.models.entities.Miembro;
import com.espe.micro_miembros.repositories.MiembroRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.List;
import java.util.Optional;
@Service
public class MiembroServiceImpl implements MiembroService{

    @Autowired
    private MiembroRepository repository;

    @Override
    public List<Miembro> findAll() {
        return (List<Miembro>) repository.findAll();
    }

    @Override
    public Optional<Miembro> findById(Long id) {
        return repository.findById(id);
    }

    @Override
    public Miembro save(Miembro mienmbro) {
        return repository.save(mienmbro);
    }

    @Override
    public void deleteById(Long id) {
        repository.deleteById(id);
    }
}
