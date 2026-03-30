package com.iran.war.service;

import com.iran.war.entity.Category;
import com.iran.war.repository.CategoryRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Optional;

@Service
@RequiredArgsConstructor
public class CategoryService {

    private final CategoryRepository categoryRepository;

    @Transactional(readOnly = true)
    public List<Category> getAllCategories() {
        return categoryRepository.findAllByOrderByNameAsc();
    }

    @Transactional(readOnly = true)
    public Optional<Category> findBySlug(String slug) {
        return categoryRepository.findBySlug(slug);
    }

    @Transactional(readOnly = true)
    public Optional<Category> findById(Long id) {
        return categoryRepository.findById(id);
    }

    @Transactional
    public Category save(Category category) {
        if (category.getSlug() == null || category.getSlug().isBlank()) {
            category.setSlug(ArticleService.generateSlug(category.getName()));
        }
        return categoryRepository.save(category);
    }

    @Transactional
    public void delete(Long id) {
        categoryRepository.deleteById(id);
    }

    @Transactional(readOnly = true)
    public long countAll() {
        return categoryRepository.count();
    }
}
