package com.espe.micro_equipos;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.cloud.openfeign.EnableFeignClients;

@EnableFeignClients
@SpringBootApplication
public class MicroEquiposApplication {

	public static void main(String[] args) {
		SpringApplication.run(MicroEquiposApplication.class, args);
	}

}
